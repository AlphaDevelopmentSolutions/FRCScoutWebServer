package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportColorSeries
import org.jetbrains.exposed.sql.ResultRow

object ReportColorSeriesTable : ModifyableTable<ReportColorSeries>("report_color_series") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)

    override fun fromResultRow(resultRow: ResultRow) =
        ReportColorSeries(
            resultRow[id],
            resultRow[reportId],
            resultRow[value],
            resultRow[lastModified]
        )
}