package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportDimension
import org.jetbrains.exposed.sql.ResultRow

object ReportDimensionTable : ModifyableTable<ReportDimension>("report_dimensions") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
    override fun fromResultRow(resultRow: ResultRow): ReportDimension {
        TODO("Not yet implemented")
    }
}