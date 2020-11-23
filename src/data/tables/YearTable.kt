package com.alphadevelopmentsolutions.data.tables

import org.jetbrains.exposed.sql.Table

object YearTable : ModifyableTable("years") {
    val number = integer("number")
    val name = varchar("name", 300)
    val startDate = datetime("start_date").nullable()
    val endDate = datetime("end_date").nullable()
    val imageUri = varchar("image_uri", 45).nullable()
}