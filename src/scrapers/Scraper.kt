package com.alphadevelopmentsolutions.scraper

import com.alphadevelopmentsolutions.Constants
import com.alphadevelopmentsolutions.data.models.MatchType
import com.alphadevelopmentsolutions.data.models.Year
import com.alphadevelopmentsolutions.data.tables.*
import com.alphadevelopmentsolutions.extensions.toByteArray
import com.alphadevelopmentsolutions.routes.Route
import com.alphadevelopmentsolutions.scrapers.models.SocialMedia
import com.alphadevelopmentsolutions.singletons.ScraperInstance
import io.ktor.application.*
import io.ktor.http.*
import io.ktor.response.*
import io.ktor.routing.*
import org.jetbrains.exposed.sql.*
import org.jetbrains.exposed.sql.transactions.TransactionManager
import org.jetbrains.exposed.sql.transactions.transaction
import org.joda.time.DateTime

object Scraper : Route {

    override val SUB_PATH: String
        get() = "/scrape/"

    override fun createRoutes(routing: Routing) {
        routing {
            route(SUB_PATH) {
                scrapeTeams(this)
                scrapeEvents(this)
                scrapeMatches(this)
            }
        }
    }

    private fun scrapeTeams(route: io.ktor.routing.Route) {
        route.get("teams") {

            val teamList: MutableList<com.alphadevelopmentsolutions.data.models.Team> = mutableListOf()
            var index = 0

            do {
                val response = ScraperInstance.getInstance().getTeams(index).execute()
                val responseBody = response.body()

                if (response.isSuccessful) {
                    responseBody?.forEach { jsonTeam ->

                        var socialMediaList: List<SocialMedia> = listOf()

                        // Temp disabled to speed up scraping
//                        val socialMediaResponse = ScraperInstance.getInstance().getSocialMedia(jsonTeam.key).execute()
//                        if (socialMediaResponse.isSuccessful) {
//                            socialMediaResponse.body()?.let { tempList ->
//                                socialMediaList = tempList
//                            }
//                        }

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
                }

                index++

            } while(response.isSuccessful && responseBody != null && responseBody.isNotEmpty())

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

    private fun scrapeEvents(route: io.ktor.routing.Route) {
        route.get("events") {

            val eventList: MutableList<com.alphadevelopmentsolutions.data.models.Event> = mutableListOf()
            val yearList: MutableList<Year> = mutableListOf()

            transaction {
                YearTable.selectAll().map {
                    yearList.add(YearTable.fromResultRow(it))
                }
            }

            yearList.forEach { year ->

                val response = ScraperInstance.getInstance().getEvents(year.number).execute()
                if (response.isSuccessful) {
                    response.body()?.forEach { jsonEvent ->
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

    private fun scrapeMatches(route: io.ktor.routing.Route) {
        route.get("matches") {

            val matchList: MutableList<com.alphadevelopmentsolutions.data.models.Match> = mutableListOf()
            val eventList: MutableList<com.alphadevelopmentsolutions.data.models.Event> = mutableListOf()
            val matchTypeList: MutableList<MatchType> = mutableListOf()

            transaction {
                EventTable
                    .leftJoin(YearTable, { YearTable.id }, { EventTable.yearId })
                    .select {
                        YearTable.number eq 2019
                    }
                    .map {
                    eventList.add(EventTable.fromResultRow(it))
                }

                MatchTypeTable.selectAll().map {
                    matchTypeList.add(MatchTypeTable.fromResultRow(it))
                }
            }

            eventList.forEach { event ->

                val response = ScraperInstance.getInstance().getMatches(event.key).execute()
                if (response.isSuccessful) {
                    response.body()?.forEach { jsonMatch ->
                        var blueAllianceTeamOneId: ByteArray? = null
                        var blueAllianceTeamTwoId: ByteArray? = null
                        var blueAllianceTeamThreeId: ByteArray? = null
                        var redAllianceTeamOneId: ByteArray? = null
                        var redAllianceTeamTwoId: ByteArray? = null
                        var redAllianceTeamThreeId: ByteArray? = null


                        transaction {
                            var blueAllianceTeamOneKey = ""
                            var blueAllianceTeamTwoKey = ""
                            var blueAllianceTeamThreeKey = ""
                            var redAllianceTeamOneKey = ""
                            var redAllianceTeamTwoKey = ""
                            var redAllianceTeamThreeKey = ""

                            jsonMatch.alliances.blueAlliance.teamKeys.forEachIndexed { index, teamKey ->
                                when (index) {
                                    0 -> blueAllianceTeamOneKey = teamKey
                                    1 -> blueAllianceTeamTwoKey = teamKey
                                    2 -> blueAllianceTeamThreeKey = teamKey
                                }
                            }

                            jsonMatch.alliances.redAlliance.teamKeys.forEachIndexed { index, teamKey ->
                                when (index) {
                                    0 -> redAllianceTeamOneKey = teamKey
                                    1 -> redAllianceTeamTwoKey = teamKey
                                    2 -> redAllianceTeamThreeKey = teamKey
                                }
                            }
                            
                            TeamTable
                                .slice(TeamTable.id, TeamTable.key)
                                .select {
                                    (TeamTable.key eq blueAllianceTeamOneKey) or
                                            (TeamTable.key eq blueAllianceTeamTwoKey) or 
                                            (TeamTable.key eq blueAllianceTeamThreeKey) or 
                                            (TeamTable.key eq redAllianceTeamOneKey) or 
                                            (TeamTable.key eq redAllianceTeamTwoKey) or 
                                            (TeamTable.key eq redAllianceTeamThreeKey)
                                }.map {
                                    when (it[TeamTable.key]) {
                                        blueAllianceTeamOneKey -> blueAllianceTeamOneId = it[TeamTable.id]   
                                        blueAllianceTeamTwoKey -> blueAllianceTeamTwoId = it[TeamTable.id]
                                        blueAllianceTeamThreeKey -> blueAllianceTeamThreeId = it[TeamTable.id]
                                        redAllianceTeamOneKey -> redAllianceTeamOneId = it[TeamTable.id]   
                                        redAllianceTeamTwoKey -> redAllianceTeamTwoId = it[TeamTable.id]
                                        redAllianceTeamThreeKey -> redAllianceTeamThreeId = it[TeamTable.id]
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
                                    if (compLevel.name.equals(matchTypeCandidate.key.name, ignoreCase = true))
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