package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportFilter
import org.jetbrains.exposed.sql.ResultRow

object ReportFilterTable : ModifyableTable<ReportFilter>("report_filters") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportFilter(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )
}