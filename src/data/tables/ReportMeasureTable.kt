package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ReportMeasure
import org.jetbrains.exposed.sql.ResultRow

object ReportMeasureTable : ModifyableTable<ReportMeasure>("report_measures") {
    var reportId = binary("report_id", 16)
    var value = varchar("value", 100)
    override fun fromResultRow(resultRow: ResultRow): ReportMeasure {
        TODO("Not yet implemented")
    }
}