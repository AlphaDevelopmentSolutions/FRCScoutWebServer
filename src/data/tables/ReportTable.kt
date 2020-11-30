package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Report
import org.jetbrains.exposed.sql.ResultRow
import org.jetbrains.exposed.sql.insert
import org.jetbrains.exposed.sql.update

object ReportTable : ModifyableTable<Report>("reports") {
    var recordId = binary("record_id", 16).nullable()
    var comment = varchar("comment", 100)
    var reporterId = binary("reporter_id", 16)
    var reportedDate = datetime("reported_date")
    var isResolved = bool("is_resolved")
    var isNotified = bool("is_notified")
    var createdDate = datetime("created_date")

    override fun fromResultRow(resultRow: ResultRow) =
        Report(
            resultRow[id],
            resultRow[recordId],
            resultRow[comment],
            resultRow[reporterId],
            resultRow[reportedDate],
            resultRow[isResolved],
            resultRow[isNotified],
            resultRow[createdDate],
            resultRow[lastModified]
        )

    override fun insert(obj: Report) =
        insert {
            it[id] = obj.id
            it[recordId] = obj.recordId
            it[comment] = obj.comment
            it[reporterId] = obj.reporterId
            it[reportedDate] = obj.reportedDate
            it[isResolved] = obj.isResolved
            it[isNotified] = obj.isNotified
            it[createdDate] = obj.createdDate
            it[lastModified] = obj.lastModified
        }

    override fun update(obj: Report) =
        update({ id eq obj.id }) {
            it[id] = obj.id
            it[recordId] = obj.recordId
            it[comment] = obj.comment
            it[reporterId] = obj.reporterId
            it[reportedDate] = obj.reportedDate
            it[isResolved] = obj.isResolved
            it[isNotified] = obj.isNotified
            it[createdDate] = obj.createdDate
            it[lastModified] = obj.lastModified
        }
}