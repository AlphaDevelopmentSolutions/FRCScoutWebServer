package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ReportMeasureTable : ModifyableTable("report_measures") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
}