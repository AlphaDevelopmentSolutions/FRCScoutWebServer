package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Event
import org.jetbrains.exposed.sql.*

object EventTable : ModifyableTable<Event>("events") {
    var yearId = binary("year_id", 16)
    var code = varchar("code", 45)
    var key = varchar("key", 45)
    var venue = varchar("venue", 300).nullable()
    var name = varchar("name", 300)
    var address = varchar("address", 200).nullable()
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

    override fun insert(obj: Event) =
        insert {
            it[id] = obj.id
            it[yearId] = obj.yearId
            it[code] = obj.code
            it[key] = obj.key
            it[venue] = obj.venue
            it[name] = obj.name
            it[address] = obj.address
            it[city] = obj.city
            it[stateProvince] = obj.stateProvince
            it[country] = obj.country
            it[startTime] = obj.startTime
            it[endTime] = obj.endTime
            it[websiteUrl] = obj.websiteUrl
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: Event, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[yearId] = obj.yearId
            it[code] = obj.code
            it[key] = obj.key
            it[venue] = obj.venue
            it[name] = obj.name
            it[address] = obj.address
            it[city] = obj.city
            it[stateProvince] = obj.stateProvince
            it[country] = obj.country
            it[startTime] = obj.startTime
            it[endTime] = obj.endTime
            it[websiteUrl] = obj.websiteUrl
            it[lastModified] = obj.lastModified
        }
}