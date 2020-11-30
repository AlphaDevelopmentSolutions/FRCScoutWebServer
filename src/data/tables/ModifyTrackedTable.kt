package com.alphadevelopmentsolutions.data.tables

import com.alphadevelopmentsolutions.data.models.ByteArrayTable
import com.google.gson.annotations.SerializedName
import org.jetbrains.exposed.dao.EntityID
import org.jetbrains.exposed.dao.IdTable
import org.jetbrains.exposed.sql.Column
import org.jetbrains.exposed.sql.Table
import java.util.*

abstract class ModifyTrackedTable<T: ByteArrayTable>(name: String = "") : ModifyableTable<T>(name) {
    var deletedDate = datetime("deleted_date").nullable()
    var deletedById = binary("deleted_by_id", 16).nullable()
    var modifiedById = binary("modified_by_id", 16)
}