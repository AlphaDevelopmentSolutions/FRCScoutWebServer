package com.alphadevelopmentsolutions.data.tables

object PenaltyTable : ByteArrayTable("penalties") {
    var failedLoginAttemptCount = integer("failed_login_attempt_count")
    var withinDuration = integer("within_duration")
    var penaltyDuration = integer("penalty_duration")
}