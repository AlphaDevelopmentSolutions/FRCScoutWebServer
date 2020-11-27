package com.alphadevelopmentsolutions.data.tables

object ReportDimensionTable : ModifyableTable("report_dimensions") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
}