package com.alphadevelopmentsolutions.data.tables

object MatchTypeTable : ModifyableTable("match_types") {
    var key = varchar("key", 4)
    var name = varchar("name", 45)
}