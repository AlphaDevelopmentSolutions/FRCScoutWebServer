package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Blacklist
import org.jetbrains.exposed.sql.ResultRow

object BlacklistTable : ByteArrayTable<Blacklist>("blacklist") {
    var ip = integer("ip")
    var added = datetime("added")
    var penaltyId = binary("penalty_id", 16)

    override fun fromResultRow(resultRow: ResultRow) =
        Blacklist(
            resultRow[id],
            resultRow[ip],
            resultRow[added],
            resultRow[penaltyId]
        )
}