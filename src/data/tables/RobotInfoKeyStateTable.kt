package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.RobotInfoKeyState
import org.jetbrains.exposed.sql.*

object RobotInfoKeyStateTable : ModifyTrackedTable<RobotInfoKeyState>("robot_info_key_states") {
    var teamAccountId = binary("team_account_id", 16)
    var yearId = binary("year_id", 16)
    var name = varchar("name", 16)
    var description = varchar("description", 200).nullable()

    override fun fromResultRow(resultRow: ResultRow) =
        RobotInfoKeyState(
            resultRow[id],
            resultRow[teamAccountId],
            resultRow[yearId],
            resultRow[name],
            resultRow[description],
            resultRow[deletedDate],
            resultRow[deletedById],
            resultRow[lastModified],
            resultRow[modifiedById]
        )

    override fun insert(obj: RobotInfoKeyState) =
        insert {
            it[id] = obj.id
            it[teamAccountId] = obj.teamAccountId
            it[yearId] = obj.yearId
            it[name] = obj.name
            it[description] = obj.description
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }

    override fun update(obj: RobotInfoKeyState, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[teamAccountId] = obj.teamAccountId
            it[yearId] = obj.yearId
            it[name] = obj.name
            it[description] = obj.description
            it[deletedDate] = obj.deletedDate
            it[deletedById] = obj.deletedById
            it[lastModified] = obj.lastModified
            it[modifiedById] = obj.modifiedById
        }
}