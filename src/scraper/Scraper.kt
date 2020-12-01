package com.alphadevelopmentsolutions.scraper

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.Credentials
import com.alphadevelopmentsolutions.data.tables.TeamTable
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.routes.Route
import com.alphadevelopmentsolutions.scraper.models.SocialMedia
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
import kotlinx.css.ins
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
                    val socialMediaList: List<SocialMedia> =
                        client.get {
                            url("https://www.thebluealliance.com/api/v3/team/${jsonTeam.key}/social_media?X-TBA-Auth-Key=${Credentials.TBA_KEY}")
                            accept(ContentType.Application.Json)
                            contentType(ContentType.Application.Json)
                            header("X-TBA-Auth-Key", Credentials.TBA_KEY)
                            userAgent("")
                        }

                    var facebookUrl: String? = null
                    var instagramUrl: String? = null
                    var twitterUrl: String? = null
                    var youtubeUrl: String? = null
                    var avatarUri: String? = null

                    socialMediaList.forEach { socialMedia ->
                        when (socialMedia.type) {
                            SocialMedia.avatar -> avatarUri = socialMedia.foreignKey
                            SocialMedia.facebook_profile -> facebookUrl = socialMedia.foreignKey
                            SocialMedia.instagram_profile -> instagramUrl = socialMedia.foreignKey
                            SocialMedia.twitter_profile -> twitterUrl = socialMedia.foreignKey
                            SocialMedia.youtube_channel -> youtubeUrl = socialMedia.foreignKey
                        }
                    }

                    teamList.add(
                        com.alphadevelopmentsolutions.data.models.Team(
                            Constants.UUID_GENERATOR.generate().toByteArray(),
                            jsonTeam.number,
                            jsonTeam.name,
                            jsonTeam.city,
                            jsonTeam.stateProvince,
                            jsonTeam.country,
                            jsonTeam.rookieYear,
                            facebookUrl,
                            instagramUrl,
                            twitterUrl,
                            youtubeUrl,
                            jsonTeam.websiteUrl,
                            avatarUri,
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