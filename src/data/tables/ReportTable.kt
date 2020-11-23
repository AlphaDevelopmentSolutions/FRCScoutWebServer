package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object ReportTable : ModifyableTable("reports") {
    var recordId = binary("record_id", 16).nullable()
    var comment = varchar("comment", 100).nullable()
    var reporterId = binary("reporter_id", 16)
    var reportedDate = datetime("reported_date")
    var isResolved = bool("is_resolved")
    var isNotified = bool("is_notified")
    var createdDate = datetime("created_date")
}