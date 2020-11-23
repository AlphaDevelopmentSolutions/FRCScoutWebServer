package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object BlacklistTable : ByteArrayTable("blacklist") {
    var ip = integer("ip")
    var added = datetime("added")
    var penaltyId = binary("penalty_id", 16)
}