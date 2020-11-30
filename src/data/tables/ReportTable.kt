package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.Report
import org.jetbrains.exposed.sql.ResultRow

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
}