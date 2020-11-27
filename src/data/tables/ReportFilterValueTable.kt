package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportFilterValue
import org.jetbrains.exposed.sql.ResultRow

object ReportFilterValueTable : ModifyableTable<ReportFilterValue>("report_filter_values") {
    var reportFilterId = binary("report_filter_id", 16)
    var value = varchar("value", 100)
    override fun fromResultRow(resultRow: ResultRow): ReportFilterValue {
        TODO("Not yet implemented")
    }
}