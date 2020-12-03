package com.alphadevelopmentsolutions.routes

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.api.AppData
import com.alphadevelopmentsolutions.api.Credentials
import com.alphadevelopmentsolutions.data.models.*
import com.alphadevelopmentsolutions.data.tables.*
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.extensions.toIP
import com.alphadevelopmentsolutions.singletons.GsonInstance
import io.ktor.application.*
import io.ktor.features.*
import io.ktor.http.*
import io.ktor.request.*
import io.ktor.response.*
import io.ktor.routing.*
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import org.jetbrains.exposed.sql.*
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime
import java.text.SimpleDateFormat
import java.util.*

object Api : Route {

    override val SUB_PATH: String = "/api/"

    override fun createRoutes(routing: Routing) {
        routing {
            route(SUB_PATH) {
                getData(this)
                setData(this)
            }
        }
    }

    private suspend fun init(call: ApplicationCall): Credentials? {
        var credentials: Credentials? = null

        val request = call.request
        val origin = request.origin
        val params = call.receiveParameters()

        val token =
            params["token"]?.let {
                UUID.fromString(it).toByteArray()
            }

        val lastModified: DateTime =
            params["since"].let {
                if (it != null) {
                    val formatter = SimpleDateFormat()
                    formatter.applyPattern("yyyy-MM-dd HH:mm:ss")
                    val date = formatter.parse(it)

                    DateTime(date.time)
                }
                else
                    DateTime(0)
            }

        transaction {
            ApiAccessLogTable.insert {
                it[id] = Constants.UUID_GENERATOR.generate().toByteArray()
                it[endpoint] = request.uri.split("?").let { uri -> if (uri.isNotEmpty()) uri[0] else request.uri }
                it[ip] = origin.remoteHost.let { ip -> (if (ip == "localhost") "127.0.0.1".toIP() else ip.toIP()) ?: 0 }
                it[userAgent] = request.userAgent() ?: ""
            }

            if (token != null) {
                var authToken: AuthToken? = null
                var user: User? = null
                val teamAccountList: MutableList<TeamAccount> = mutableListOf()

                AuthTokenTable.select { AuthTokenTable.id eq token }.map {
                    authToken = AuthTokenTable.fromResultRow(it)
                }

                authToken?.let { tempAuthToken ->
                    UserTable.select { UserTable.id eq tempAuthToken.userId }.map {
                        user = UserTable.fromResultRow(it)
                    }

                    user?.let { tempUser ->
                        TeamAccountTable
                            .leftJoin(UserTeamAccountListTable, { UserTeamAccountListTable.teamAccountId }, { TeamAccountTable.id })
                            .slice(TeamAccountTable.columns)
                            .select { UserTeamAccountListTable.userId eq tempUser.id }
                            .map {
                                teamAccountList.add(TeamAccountTable.fromResultRow(it))
                            }

                        credentials =
                            Credentials(
                                tempAuthToken,
                                tempUser,
                                teamAccountList,
                                lastModified
                            )
                    }
                }
            }
        }

        call.respond(HttpStatusCode.Unauthorized)

        return credentials
    }

    /**
     * Gets data from the database
     */
    private fun getData(route: io.ktor.routing.Route) =
        route.post("get") {
            withContext(Dispatchers.IO) {
                val credentials = init(call) ?: return@withContext

                val checklistItemList = mutableListOf<ChecklistItem>()
                val checklistItemResultList = mutableListOf<ChecklistItemResult>()
                val dataTypeList = mutableListOf<DataType>()
                val eventList = mutableListOf<Event>()
                val eventTeamListList = mutableListOf<EventTeamList>()
                val matchList = mutableListOf<Match>()
                val matchTypeList = mutableListOf<MatchType>()
                val robotInfoList = mutableListOf<RobotInfo>()
                val robotInfoKeyList = mutableListOf<RobotInfoKey>()
                val robotInfoKeyStateList = mutableListOf<RobotInfoKeyState>()
                val robotMediaList = mutableListOf<RobotMedia>()
                val roleList = mutableListOf<Role>()
                val scoutCardInfoList = mutableListOf<ScoutCardInfo>()
                val scoutCardInfoKeyList = mutableListOf<ScoutCardInfoKey>()
                val scoutCardInfoKeyStateList = mutableListOf<ScoutCardInfoKeyState>()
                val teamList = mutableListOf<Team>()
                val userList = mutableListOf<User>()
                val userRoleList = mutableListOf<UserRole>()
                val userTeamAccountListList = mutableListOf<UserTeamAccountList>()
                val yearList = mutableListOf<Year>()

                val teamAccountIdList: MutableList<ByteArray> = mutableListOf()

                credentials.teamAccountList.forEach {
                    teamAccountIdList.add(it.id)
                }

                transaction {
                    ChecklistItemTable
                        .select {
                            (ChecklistItemTable.teamAccountId inList teamAccountIdList) and
                                    (ChecklistItemTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            checklistItemList.add(ChecklistItemTable.fromResultRow(it))
                        }

                    ChecklistItemResultTable
                        .leftJoin(ChecklistItemTable, { checklistItemId }, { ChecklistItemTable.id })
                        .select {
                            (ChecklistItemTable.teamAccountId inList teamAccountIdList) and
                                    (ChecklistItemResultTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            checklistItemResultList.add(ChecklistItemResultTable.fromResultRow(it))
                        }

                    DataTypeTable
                        .select { DataTypeTable.lastModified greater credentials.lastModified }
                        .map {
                            dataTypeList.add(DataTypeTable.fromResultRow(it))
                        }

                    EventTable
                        .select { EventTable.lastModified greater credentials.lastModified}
                        .map {
                            eventList.add(EventTable.fromResultRow(it))
                        }

                    EventTeamListTable
                        .select { EventTeamListTable.lastModified greater credentials.lastModified }
                        .map {
                            eventTeamListList.add(EventTeamListTable.fromResultRow(it))
                        }

                    MatchTable
                        .select { MatchTable.lastModified greater credentials.lastModified }
                        .map {
                            matchList.add(MatchTable.fromResultRow(it))
                        }

                    MatchTypeTable
                        .select { MatchTypeTable.lastModified greater credentials.lastModified }
                        .map {
                            matchTypeList.add(MatchTypeTable.fromResultRow(it))
                        }

                    RobotInfoTable
                        .leftJoin(RobotInfoKeyTable, { RobotInfoKeyTable.id }, { RobotInfoTable.keyId })
                        .leftJoin(RobotInfoKeyStateTable, { RobotInfoKeyStateTable.id }, { RobotInfoKeyTable.stateId })
                        .select {
                            (RobotInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (RobotInfoTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            robotInfoList.add(RobotInfoTable.fromResultRow(it))
                        }

                    RobotInfoKeyTable
                        .leftJoin(RobotInfoKeyStateTable, { RobotInfoKeyStateTable.id }, { RobotInfoKeyTable.stateId })
                        .select {
                            (RobotInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (RobotInfoKeyTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            robotInfoKeyList.add(RobotInfoKeyTable.fromResultRow(it))
                        }

                    RobotInfoKeyStateTable
                        .select {
                            (RobotInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (RobotInfoKeyStateTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            robotInfoKeyStateList.add(RobotInfoKeyStateTable.fromResultRow(it))
                        }

                    RobotInfoKeyStateTable
                        .select {
                            (RobotInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (RobotInfoKeyStateTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            robotInfoKeyStateList.add(RobotInfoKeyStateTable.fromResultRow(it))
                        }

                    RoleTable
                        .select {
                            (RoleTable.teamAccountId inList teamAccountIdList) and
                                    (RoleTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            roleList.add(RoleTable.fromResultRow(it))
                        }

                    ScoutCardInfoTable
                        .leftJoin(ScoutCardInfoKeyTable, { ScoutCardInfoKeyTable.id }, { ScoutCardInfoTable.keyId })
                        .leftJoin(ScoutCardInfoKeyStateTable, { ScoutCardInfoKeyStateTable.id }, { ScoutCardInfoKeyTable.stateId })
                        .select {
                            (ScoutCardInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (ScoutCardInfoTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            scoutCardInfoList.add(ScoutCardInfoTable.fromResultRow(it))
                        }

                    ScoutCardInfoKeyTable
                        .leftJoin(ScoutCardInfoKeyStateTable, { ScoutCardInfoKeyStateTable.id }, { ScoutCardInfoKeyTable.stateId })
                        .select {
                            (ScoutCardInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (ScoutCardInfoKeyTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            scoutCardInfoKeyList.add(ScoutCardInfoKeyTable.fromResultRow(it))
                        }

                    ScoutCardInfoKeyStateTable
                        .select {
                            (ScoutCardInfoKeyStateTable.teamAccountId inList teamAccountIdList) and
                                    (ScoutCardInfoKeyStateTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            scoutCardInfoKeyStateList.add(ScoutCardInfoKeyStateTable.fromResultRow(it))
                        }

                    TeamTable
                        .select {
                            TeamTable.lastModified greater credentials.lastModified
                        }
                        .map {
                            teamList.add(TeamTable.fromResultRow(it))
                        }

                    UserTable
                        .leftJoin(UserTeamAccountListTable, { UserTeamAccountListTable.userId }, { UserTable.id })
                        .select {
                            (UserTeamAccountListTable.teamAccountId inList teamAccountIdList) and
                                    (UserTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            userList.add(UserTable.fromResultRow(it))
                        }

                    UserRoleTable
                        .leftJoin(UserTeamAccountListTable, { UserTeamAccountListTable.id }, { UserRoleTable.userTeamAccountListId })
                        .select {
                            (UserTeamAccountListTable.teamAccountId inList teamAccountIdList) and
                                    (UserRoleTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            userRoleList.add(UserRoleTable.fromResultRow(it))
                        }

                    UserTeamAccountListTable
                        .select {
                            (UserTeamAccountListTable.teamAccountId inList teamAccountIdList) and
                                    (UserTeamAccountListTable.lastModified greater credentials.lastModified)
                        }
                        .map {
                            userTeamAccountListList.add(UserTeamAccountListTable.fromResultRow(it))
                        }

                    YearTable
                        .select {
                            YearTable.lastModified greater credentials.lastModified
                        }
                        .map {
                            yearList.add(YearTable.fromResultRow(it))
                        }

                }

                val teamAccountList: MutableList<TeamAccount> = mutableListOf()

                credentials.teamAccountList.forEach {
                    if (it.lastModified > credentials.lastModified)
                        teamAccountList.add(it)
                }

                val appData =
                    AppData(
                        checklistItemList,
                        checklistItemResultList,
                        dataTypeList,
                        eventList,
                        eventTeamListList,
                        matchList,
                        matchTypeList,
                        robotInfoList,
                        robotInfoKeyList,
                        robotInfoKeyStateList,
                        robotMediaList,
                        roleList,
                        scoutCardInfoList,
                        scoutCardInfoKeyList,
                        scoutCardInfoKeyStateList,
                        teamList,
                        teamAccountList,
                        userList,
                        userRoleList,
                        userTeamAccountListList,
                        yearList
                    )

                call.respondText(
                    GsonInstance.getInstance().toJson(appData),
                    ContentType.Application.Json
                )
            }
        }

    private fun setData(route: io.ktor.routing.Route) =
        route.get("set") {
            call.respondText("Called SET DATA")
        }
}