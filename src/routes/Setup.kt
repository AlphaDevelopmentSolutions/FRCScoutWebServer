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

            val team =
                transaction {
                    TeamTable
                        .select { TeamTable.number eq 5885 }
                        .map { TeamTable.fromResultRow(it) }
                        .single()
                }

            val teamAccount =
                TeamAccount(
                    Constants.UUID_GENERATOR.generate().toByteArray(),
                    team.id,
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

            transaction {
                exec("SET FOREIGN_KEY_CHECKS=0;")
            }

            transaction {
                UserTable.upsert(newUser)
                AuthTokenTable.upsert(authToken)
                TeamAccountTable.upsert(teamAccount)
                UserTeamAccountListTable.upsert(userTeamAccountList)
            }

            call.respondText(
                "Success",
                ContentType.Application.Json
            )
        }
}