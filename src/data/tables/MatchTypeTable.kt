package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.MatchType
import org.jetbrains.exposed.sql.ResultRow

object MatchTypeTable : ModifyableTable<MatchType>("match_types") {
    var key = varchar("key", 4)
    var name = varchar("name", 45)

    override fun fromResultRow(resultRow: ResultRow) =
        MatchType(
            resultRow[id],
            resultRow[key],
            resultRow[name],
            resultRow[lastModified]
        )
}