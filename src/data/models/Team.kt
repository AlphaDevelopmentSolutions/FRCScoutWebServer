package com.alphadevelopmentsolutions.data.models

import org.joda.time.DateTime

class Team(
    override var id: ByteArray,
    val number: Int,
    val name: String,
    val city: String,
    val stateProvince: String,
    val country: String,
    val rookieYear: Int,
    val facebookUrl: String,
    val instagramUrl: String,
    val twitterUrl: String,
    val youtubeUrl: String,
    val websiteUrl: String,
    val avatarUri: String,
    override val deletedDate: DateTime?,
    override val deletedById: ByteArray?,
    override val lastModified: DateTime,
    override val modifiedById: ByteArray
) : ModifyTrackedTable(id, deletedDate, deletedById, lastModified, modifiedById) {
    override fun toString(): String {
        TODO("Not yet implemented")
    }

    companion object {
        enum class State {

        }
    }
}