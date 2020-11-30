package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ScoutCardInfo
import org.jetbrains.exposed.sql.ResultRow

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
}