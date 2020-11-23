package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.TeamInvitation
import com.alphadevelopmentsolutions.data.models.UserTeamAccountList
import com.google.gson.annotations.SerializedName

object PenaltyTable : ByteArrayTable("penalties") {
    var failedLoginAttemptCount = integer("failed_login_attempt_count")
    var withinDuration = integer("within_duration")
    var penaltyDuration = integer("penalty_duration")
}