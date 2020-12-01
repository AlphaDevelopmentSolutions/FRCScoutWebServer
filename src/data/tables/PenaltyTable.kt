package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Penalty
import org.jetbrains.exposed.sql.*

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

    override fun insert(obj: Penalty) =
        insert {
            it[id] = obj.id
            it[failedLoginAttemptCount] = obj.failedLoginAttemptCount
            it[withinDuration] = obj.withinDuration
            it[penaltyDuration] = obj.penaltyDuration
        }

    override fun update(obj: Penalty, where: (SqlExpressionBuilder.() -> Op<Boolean>)?): Int =
        update(where ?: { id eq obj.id }) {
            it[failedLoginAttemptCount] = obj.failedLoginAttemptCount
            it[withinDuration] = obj.withinDuration
            it[penaltyDuration] = obj.penaltyDuration
        }
}