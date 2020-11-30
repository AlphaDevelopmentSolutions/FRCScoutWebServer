package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Event
import org.jetbrains.exposed.sql.ResultRow

object EventTable : ModifyableTable<Event>("events") {
    var yearId = binary("year_id", 16)
    var code = varchar("code", 45)
    var key = varchar("key", 45)
    var venue = varchar("venue", 300)
    var name = varchar("name", 300)
    var address = varchar("address", 200)
    var city = varchar("city", 45).nullable()
    var stateProvince = varchar("state_province", 45).nullable()
    var country = varchar("country", 45).nullable()
    var startTime = datetime("start_time").nullable()
    var endTime = datetime("end_time").nullable()
    var websiteUrl = varchar("website_url", 200).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        Event(
            resultRow[id],
            resultRow[yearId],
            resultRow[code],
            resultRow[key],
            resultRow[venue],
            resultRow[name],
            resultRow[address],
            resultRow[city],
            resultRow[stateProvince],
            resultRow[country],
            resultRow[startTime],
            resultRow[endTime],
            resultRow[websiteUrl],
            resultRow[lastModified]
        )
}