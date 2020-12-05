package com.alphadevelopmentsolutions.routes

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.api.AppData
import com.alphadevelopmentsolutions.api.Credentials
import com.alphadevelopmentsolutions.data.models.*
import com.alphadevelopmentsolutions.data.tables.*
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.extensions.toIP
import com.alphadevelopmentsolutions.extensions.toPassword
import com.alphadevelopmentsolutions.singletons.GsonInstance
import io.ktor.application.*
import io.ktor.features.*
import io.ktor.http.*
import io.ktor.request.*
import io.ktor.response.*
import io.ktor.routing.*
import org.jetbrains.exposed.sql.*
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime
import java.util.*

object Setup : Route {

    override fun createRoutes(routing: Routing) {
        routing {
            route(SUB_PATH) {
                setupBaseData(this)
            }
        }
    }

    private fun setupBaseData(route: io.ktor.routing.Route) =
        route.get("setup") {

            transaction {
                exec("SET FOREIGN_KEY_CHECKS=0;")
            }

            transaction {
                UserTable.deleteAll()
                AuthTokenTable.deleteAll()
                TeamAccountTable.deleteAll()
                UserTeamAccountListTable.deleteAll()
                YearTable.deleteAll()
                YearTable.deleteAll()
                YearTable.deleteAll()
                MatchTypeTable.deleteAll()
            }

            transaction {
                TeamTable
                    .slice(TeamTable.id)
                    .select { TeamTable.number eq 5885 }
                    .map { it[TeamTable.id] }
                    .singleOrNull()
            }?.let { teamId ->
                val newUser =
                    User(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        "Griffin",
                        "Sorrentino",
                        "griffinsorrentino@gmail.com",
                        "griffinsorrentino",
                        "test description",
                        "testuri",
                        null,
                        null,
                        DateTime(),
                        ByteArray(0)
                    ).apply {
                        password = "testingpassword123".toPassword()
                    }

                val authToken =
                    AuthToken(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        newUser.id,
                        "127.0.0.1".toIP() ?: 0,
                        DateTime(System.currentTimeMillis() + System.currentTimeMillis())
                    )

                val teamAccount =
                    TeamAccount(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        teamId,
                        "Villanova Team Account",
                        null,
                        "wiredcats",
                        newUser.id,
                        null,
                        null,
                        null,
                        DateTime(),
                        null,
                        null,
                        DateTime(),
                        newUser.id
                    )

                val userTeamAccountList =
                    UserTeamAccountList(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        newUser.id,
                        teamAccount.id,
                        UserTeamAccountList.Companion.State.ENABLED,
                        null,
                        null,
                        DateTime(),
                        newUser.id
                    )

                val year =
                    Year(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        2018,
                        "FIRST Power Up",
                        null,
                        null,
                        null,
                        DateTime()
                    )

                val year2 =
                    Year(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        2019,
                        "Destination: Deep Space",
                        null,
                        null,
                        null,
                        DateTime()
                    )

                val year3 =
                    Year(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        2020,
                        "Infinite Recharge",
                        null,
                        null,
                        null,
                        DateTime()
                    )

                val matchType =
                    MatchType(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        MatchType.Key.qm,
                        "Qual",
                        DateTime()
                    )

                val matchType2 =
                    MatchType(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        MatchType.Key.ef,
                        "Exo Final",
                        DateTime()
                    )

                val matchType3 =
                    MatchType(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        MatchType.Key.qf,
                        "Quarter Final",
                        DateTime()
                    )

                val matchType4 =
                    MatchType(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        MatchType.Key.sf,
                        "Semi Final",
                        DateTime()
                    )

                val matchType5 =
                    MatchType(
                        Constants.UUID_GENERATOR.generate().toByteArray(),
                        MatchType.Key.f,
                        "Final",
                        DateTime()
                    )

                transaction {
                    UserTable.upsert(newUser)
                    AuthTokenTable.upsert(authToken)
                    TeamAccountTable.upsert(teamAccount)
                    UserTeamAccountListTable.upsert(userTeamAccountList)
                    YearTable.upsert(year) { YearTable.number eq year.number }
                    YearTable.upsert(year2) { YearTable.number eq year2.number }
                    YearTable.upsert(year3) { YearTable.number eq year3.number }
                    MatchTypeTable.upsert(matchType)
                    MatchTypeTable.upsert(matchType2)
                    MatchTypeTable.upsert(matchType3)
                    MatchTypeTable.upsert(matchType4)
                    MatchTypeTable.upsert(matchType5)
                }
            }

            call.respondText(
                "Success",
                ContentType.Application.Json
            )
        }
}