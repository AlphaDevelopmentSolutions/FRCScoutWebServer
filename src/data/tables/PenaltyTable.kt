package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Penalty
import org.jetbrains.exposed.sql.ResultRow

object PenaltyTable : ByteArrayTable<Penalty>("penalties") {
    var failedLoginAttemptCount = integer("failed_login_attempt_count")
    var withinDuration = integer("within_duration")
    var penaltyDuration = integer("penalty_duration")

    override fun fromResultRow(resultRow: ResultRow) =
        Penalty(
            resultRow[id],
            resultRow[failedLoginAttemptCount],
            resultRow[withinDuration],
            resultRow[penaltyDuration]
        )
}