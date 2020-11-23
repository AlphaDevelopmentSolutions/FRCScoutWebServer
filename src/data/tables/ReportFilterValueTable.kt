package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ReportFilterValueTable : ModifyableTable("report_filter_values") {
    var reportFilterId = binary("report_filter_id", 16)
    var value = varchar("value", 100)
}