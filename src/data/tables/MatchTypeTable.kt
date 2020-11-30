package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.MatchType
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

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

    override fun insert(obj: MatchType) =
        insert {
            it[id] = obj.id
            it[key] = obj.key
            it[name] = obj.name
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: MatchType) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[key] = obj.key
            it[name] = obj.name
            it[lastModified] = obj.lastModified
        }
}