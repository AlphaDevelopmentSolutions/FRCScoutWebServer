package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ScoutCardInfo
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ScoutCardInfoTable : ModifyTrackedTable<ScoutCardInfo>("scout_card_info") {
    var matchId = binary("match_id", 16)
    var teamId = binary("team_id", 16)
    var keyId = binary("key_id", 16)
    var value = varchar("value", 200)
    var completedById = binary("completed_by_id", 16)
    var isPublic = bool("is_public")

    override fun fromResultRow(resultRow: ResultRow) =
        ScoutCardInfo(
            resultRow[id],
            resultRow[matchId],
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

    override fun insert(obj: ScoutCardInfo) =
        insert {
            it[id] = obj.id
            it[matchId] = obj.matchId
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

    override fun update(obj: ScoutCardInfo) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[matchId] = obj.matchId
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