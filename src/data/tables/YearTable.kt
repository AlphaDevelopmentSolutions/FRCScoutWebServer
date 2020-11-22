package com.alphadevelopmentsolutions.data.tables

import org.jetbrains.exposed.sql.Table

object YearTable : Table("years") {
    val id = binary("id", 16)
    val number = integer("number")
    val name = varchar("name", 30)
    val startDate = datetime("start_date")
    val endDate = datetime("end_date")
    val imageUri = varchar("image_uri", 45)
    val lastModified = datetime("last_modified")
}