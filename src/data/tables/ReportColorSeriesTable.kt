package com.alphadevelopmentsolutions.data.tables

object ReportColorSeriesTable : ModifyableTable("report_color_series") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
}