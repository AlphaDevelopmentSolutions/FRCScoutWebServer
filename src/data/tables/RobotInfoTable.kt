package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotInfo
import org.jetbrains.exposed.sql.*

object RobotInfoTable : ModifyTrackedTable<RobotInfo>("robot_info") {
    var eventId = binary("event_id", 16)
    var teamId = binary("team_id", 16)
    var keyId = binary("key_id", 16)
    var value = varchar("value", 200)
    var completedById = binary("completed_by_id", 16)
    var isPublic = bool("is_public")

    override fun fromResultRow(resultRow: ResultRow) =
        RobotInfo(
            resultRow[id],
            resultRow[eventId],
            resultRow[teamId],
            resultRow[keyId],
            resultRow[value],
            resultRow[completedById],
            resultRow[isPublic],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: RobotInfo) =
        insert {
            it[id] = obj.id
            it[eventId] = obj.eventId
            it[teamId] = obj.teamId
            it[keyId] = obj.keyId
            it[value] = obj.value
            it[completedById] = obj.completedById
            it[isPublic] = obj.isPublic
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: RobotInfo, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[eventId] = obj.eventId
            it[teamId] = obj.teamId
            it[keyId] = obj.keyId
            it[value] = obj.value
            it[completedById] = obj.completedById
            it[isPublic] = obj.isPublic
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}