package com.alphadevelopmentsolutions.scraper

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.Credentials
import com.alphadevelopmentsolutions.data.models.MatchType
import com.alphadevelopmentsolutions.data.models.Year
import com.alphadevelopmentsolutions.data.tables.*
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.routes.Route
import com.alphadevelopmentsolutions.scraper.models.Event
import com.alphadevelopmentsolutions.scraper.models.Match
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
import org.jetbrains.exposed.sql.select
import org.jetbrains.exposed.sql.selectAll
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime

object Scraper : Route {

    override val SUB_PATH: String
        get() = "/scrape/"

    private val client by lazy {
        HttpClient(Apache) {
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
    }

    override fun createRoutes(routing: Routing) {
        routing {
            route(SUB_PATH) {
                teams(this)
                events(this)
                matches(this)
            }
        }
    }

    private fun teams(route: io.ktor.routing.Route) {
        route.get("teams") {

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
                            jsonTeam.key,
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

    private fun events(route: io.ktor.routing.Route) {
        route.get("events") {

            val eventList: MutableList<com.alphadevelopmentsolutions.data.models.Event> = mutableListOf()
            val yearList: MutableList<Year> = mutableListOf()
            var response: List<Event>

            transaction {
                YearTable.selectAll().map {
                    yearList.add(YearTable.fromResultRow(it))
                }
            }

            yearList.forEach { year ->
                response =
                    client.get {
                        url("https://www.thebluealliance.com/api/v3/events/${year.number}?X-TBA-Auth-Key=${Credentials.TBA_KEY}")
                        accept(ContentType.Application.Json)
                        contentType(ContentType.Application.Json)
                        header("X-TBA-Auth-Key", Credentials.TBA_KEY)
                        userAgent("")
                    }

                response.forEach { jsonEvent ->
                    eventList.add(
                        com.alphadevelopmentsolutions.data.models.Event(
                            Constants.UUID_GENERATOR.generate().toByteArray(),
                            year.id,
                            jsonEvent.code,
                            jsonEvent.key,
                            jsonEvent.venue,
                            jsonEvent.name,
                            jsonEvent.address,
                            jsonEvent.city,
                            jsonEvent.stateProvince,
                            jsonEvent.country,
                            jsonEvent.startTime,
                            jsonEvent.endTime,
                            jsonEvent.websiteUrl,
                            DateTime()
                        )
                    )
                }
            }

            transaction {
                eventList.forEach { event ->
                    EventTable.upsert(
                        event
                    ) { EventTable.key eq event.key }
                }
            }

            call.respondText("Success", ContentType.Application.JavaScript)
        }
    }

    private fun matches(route: io.ktor.routing.Route) {
        route.get("matches") {

            val matchList: MutableList<com.alphadevelopmentsolutions.data.models.Match> = mutableListOf()
            val eventList: MutableList<com.alphadevelopmentsolutions.data.models.Event> = mutableListOf()
            val matchTypeList: MutableList<MatchType> = mutableListOf()
            var response: List<Match>

            transaction {
                EventTable.selectAll().map {
                    eventList.add(EventTable.fromResultRow(it))
                }

                MatchTypeTable.selectAll().map {
                    matchTypeList.add(MatchTypeTable.fromResultRow(it))
                }
            }

            eventList.forEach { event ->
                response =
                    client.get {
                        url("https://www.thebluealliance.com/api/v3/event/${event.key}/matches?X-TBA-Auth-Key=${Credentials.TBA_KEY}")
                        accept(ContentType.Application.Json)
                        contentType(ContentType.Application.Json)
                        header("X-TBA-Auth-Key", Credentials.TBA_KEY)
                        userAgent("")
                    }

                response.forEach { jsonMatch ->

                    var blueAllianceTeamOneId: ByteArray? = null
                    var blueAllianceTeamTwoId: ByteArray? = null
                    var blueAllianceTeamThreeId: ByteArray? = null
                    var redAllianceTeamOneId: ByteArray? = null
                    var redAllianceTeamTwoId: ByteArray? = null
                    var redAllianceTeamThreeId: ByteArray? = null


                    transaction {
                        jsonMatch.alliances.blueAlliance.teamKeys.forEachIndexed { index, teamKey ->
                            TeamTable
                                .slice(TeamTable.id)
                                .select { TeamTable.key eq teamKey }.map {
                                    when (index) {
                                        0 -> blueAllianceTeamOneId = it[TeamTable.id]
                                        1 -> blueAllianceTeamTwoId = it[TeamTable.id]
                                        2 -> blueAllianceTeamThreeId = it[TeamTable.id]
                                    }
                                }
                        }

                        jsonMatch.alliances.redAlliance.teamKeys.forEachIndexed { index, teamKey ->
                            TeamTable
                                .slice(TeamTable.id)
                                .select { TeamTable.key eq teamKey }.map {
                                    when (index) {
                                        0 -> redAllianceTeamOneId = it[TeamTable.id]
                                        1 -> redAllianceTeamTwoId = it[TeamTable.id]
                                        2 -> redAllianceTeamThreeId = it[TeamTable.id]
                                    }
                                }
                        }
                    }

                    if (
                        blueAllianceTeamOneId != null &&
                        blueAllianceTeamTwoId != null &&
                        blueAllianceTeamThreeId != null &&
                        redAllianceTeamOneId != null &&
                        redAllianceTeamTwoId != null &&
                        redAllianceTeamThreeId != null
                    ) {

                        jsonMatch.compLevel.let { compLevel ->
                            var matchType: MatchType? = null

                            matchTypeList.forEach { matchTypeCandidate ->
                                if (compLevel.name.equals(matchTypeCandidate.name, ignoreCase = true))
                                    matchType = matchTypeCandidate
                            }

                            matchType
                        }?.let { matchType ->
                            matchList.add(
                                com.alphadevelopmentsolutions.data.models.Match(
                                    Constants.UUID_GENERATOR.generate().toByteArray(),
                                    event.id,
                                    jsonMatch.key,
                                    matchType.id,
                                    jsonMatch.setNumber,
                                    jsonMatch.matchNumber,
                                    blueAllianceTeamOneId!!,
                                    blueAllianceTeamTwoId!!,
                                    blueAllianceTeamThreeId!!,
                                    redAllianceTeamOneId!!,
                                    redAllianceTeamTwoId!!,
                                    redAllianceTeamThreeId!!,
                                    jsonMatch.alliances.blueAlliance.score,
                                    jsonMatch.alliances.redAlliance.score,
                                    DateTime(jsonMatch.time),
                                    DateTime()
                                )
                            )
                        }
                    }

                }
            }

            transaction {
                matchList.forEach { match ->
                    MatchTable.upsert(
                        match
                    ) { MatchTable.key eq match.key }
                }
            }

            call.respondText("Success", ContentType.Application.JavaScript)
        }
    }
}