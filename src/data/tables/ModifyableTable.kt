package com.alphadevelopmentsolutions.data.tables

import org.jetbrains.exposed.dao.EntityID
import org.jetbrains.exposed.dao.IdTable
import org.jetbrains.exposed.sql.Column
import org.jetbrains.exposed.sql.Table
import java.util.*

open class ModifyableTable(name: String = "") : ByteArrayTable(name) {
    val lastModified = datetime("last_modified")
}