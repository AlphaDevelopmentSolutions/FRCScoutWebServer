package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

abstract class ModifyTrackedTable(
    override var id: ByteArray,
    open val deletedDate: DateTime?,
    open val deletedById: ByteArray?,
    override val lastModified: DateTime,
    open val modifiedById: ByteArray
) : ModifyableTable(id, lastModified)