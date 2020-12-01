package com.alphadevelopmentsolutions.scraper

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.data.tables.TeamTable
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.routes.Route
import com.alphadevelopmentsolutions.scraper.models.Team
import io.ktor.application.*
import io.ktor.client.*
import io.ktor.client.engine.apache.*
import io.ktor.client.features.*
import io.ktor.client.features.json.*
import io.ktor.client.features.logging.*
import io.ktor.client.request.*
import io.ktor.http.*
import io.ktor.response.*
import io.ktor.routing.*
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime

object Scraper : Route {

    override fun createRoutes(routing: Routing) {
        routing {
            route(SUB_PATH) {
                scrape(this)
            }
        }
    }

    private fun scrape(route: io.ktor.routing.Route) {
        route.get("scrape") {
            val client = HttpClient(Apache) {
                install(HttpTimeout) {
                }

                install(JsonFeature) {
                    serializer = GsonSerializer()
                }

                install(Logging) {
                    level = LogLevel.HEADERS
                }

                BrowserUserAgent()
            }

            val teamList: MutableList<com.alphadevelopmentsolutions.data.models.Team> = mutableListOf()
            var response: List<Team>
            var index = 0

            do {
                response =
                    client.get {
                        url("https://www.thebluealliance.com/api/v3/teams/$index?X-TBA-Auth-Key=${Credentials.TBA_KEY}")
                        accept(ContentType.Application.Json)
                        contentType(ContentType.Application.Json)
                        header("X-TBA-Auth-Key", Credentials.TBA_KEY)
                        userAgent("")
                    }

                response.forEach { jsonTeam ->
                    teamList.add(
                        com.alphadevelopmentsolutions.data.models.Team(
                            Constants.UUID_GENERATOR.generate().toByteArray(),
                            jsonTeam.number,
                            jsonTeam.name,
                            jsonTeam.city,
                            jsonTeam.stateProvince,
                            jsonTeam.country,
                            jsonTeam.rookieYear,
                            null,
                            null,
                            null,
                            null,
                            jsonTeam.websiteUrl,
                            null,
                            DateTime()
                        )
                    )
                }

                index++

            } while(response.isNotEmpty())

            transaction {
                teamList.forEach {
                    TeamTable.upsert(
                        it
                    ) { TeamTable.number eq it.number }
                }
            }

            call.respondText("Success", ContentType.Application.JavaScript)
        }
    }
}