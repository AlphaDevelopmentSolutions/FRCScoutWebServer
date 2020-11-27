package com.alphadevelopmentsolutions.data.tables

object ReportMeasureTable : ModifyableTable("report_measures") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
}