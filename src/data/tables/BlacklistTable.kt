package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Blacklist
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

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

    override fun insert(obj: Blacklist) =
        insert {
            it[id] = obj.id
            it[ip] = obj.ip
            it[added] = obj.added
            it[penaltyId] = obj.penaltyId
        }

    override fun update(obj: Blacklist) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[ip] = obj.ip
            it[added] = obj.added
            it[penaltyId] = obj.penaltyId
        }
}