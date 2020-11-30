package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Team
import kotlinx.html.attributesMapOf
import org.jetbrains.exposed.sql.ResultRow

object TeamTable : ModifyTrackedTable<Team>("teams") {
    var number = integer("number")
    var name = varchar("name", 300)
    var city = varchar("city", 200).nullable()
    var stateProvince = varchar("state_province", 200).nullable()
    var country = varchar("country", 200).nullable()
    var rookieYear = integer("rookie_year").nullable()
    var facebookUrl = varchar("facebook_url", 300).nullable()
    var instagramUrl = varchar("instagram_url", 300).nullable()
    var twitterUrl = varchar("twitter_url", 300).nullable()
    var youtubeUrl = varchar("youtube_url", 300).nullable()
    var websiteUrl = varchar("website_url", 300).nullable()
    var avatarUri = varchar("avatar_uri", 100).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        Team(
            resultRow[id],
            resultRow[number],
            resultRow[name],
            resultRow[city],
            resultRow[stateProvince],
            resultRow[country],
            resultRow[rookieYear],
            resultRow[facebookUrl],
            resultRow[instagramUrl],
            resultRow[twitterUrl],
            resultRow[youtubeUrl],
            resultRow[websiteUrl],
            resultRow[avatarUri],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )
}