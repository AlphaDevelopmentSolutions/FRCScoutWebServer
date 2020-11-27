package com.alphadevelopmentsolutions.data.tables

object BlacklistTable : ByteArrayTable("blacklist") {
    var ip = integer("ip")
    var added = datetime("added")
    var penaltyId = binary("penalty_id", 16)
}