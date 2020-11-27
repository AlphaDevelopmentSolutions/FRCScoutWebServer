package com.alphadevelopmentsolutions.data.tables

object ReportFilterValueTable : ModifyableTable("report_filter_values") {
    var reportFilterId = binary("report_filter_id", 16)
    var value = varchar("value", 100)
}