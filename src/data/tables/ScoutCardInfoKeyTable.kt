package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ScoutCardInfoKeyTable : ModifyTrackedTable("scout_card_info_keys") {
    var stateId = binary("state_id", 16)
    var dataTypeId = binary("data_type_id", 16)
    var name = varchar("name", 100)
    var description = varchar("description", 200).nullable()
    var order = integer("order")
    var min = integer("min").nullable()
    var max = integer("max").nullable()
    var nullZeros = bool("null_zeros").nullable()
    var includeInReports = bool("include_in_reports").nullable()
}