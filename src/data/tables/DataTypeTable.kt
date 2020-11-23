package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object DataTypeTable : ModifyTrackedTable("data_types") {
    var name = varchar("name", 16)
    var canMax = bool("can_max")
    var canMin = bool("can_min")
    var canNullZeros = bool("can_null_zeros")
    var canReport = bool("can_report")
}