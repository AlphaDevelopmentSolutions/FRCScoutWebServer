package com.alphadevelopmentsolutions.singletons

import com.alphadevelopmentsolutions.Credentials
import com.alphadevelopmentsolutions.scrapers.models.Event
import com.alphadevelopmentsolutions.scrapers.models.Match
import com.alphadevelopmentsolutions.scrapers.models.SocialMedia
import com.alphadevelopmentsolutions.scrapers.models.Team
import okhttp3.Interceptor
import okhttp3.OkHttpClient
import okhttp3.Response
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Call
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.GET
import retrofit2.http.Path
import java.util.*
import java.util.concurrent.TimeUnit

interface ScraperInstance {

    @GET("teams/{index}")
    fun getTeams(@Path(value = "index", encoded = true) index: Int): Call<List<Team>>

    @GET("team/{key}/social_media")
    fun getSocialMedia(@Path(value = "key", encoded = true) key: String): Call<List<SocialMedia>>

    @GET("events/{yearNumber}")
    fun getEvents(@Path(value = "yearNumber", encoded = true) yearNumber: Int): Call<List<Event>>

    @GET("event/{key}/matches")
    fun getMatches(@Path(value = "key", encoded = true) key: String): Call<List<Match>>

    companion object {
        private var retrofitInstance: Retrofit? = null
        private var okHttpInstance: OkHttpClient? = null
        private var instance: ScraperInstance? = null
        private var apiUrl: String? = null
        private val loggingInterceptor by lazy {
            HttpLoggingInterceptor().apply { level = HttpLoggingInterceptor.Level.BODY }
        }

        /**
         * Creates an new [Retrofit] instance and stores it into [retrofitInstance]
         * @param context [Context] current app context
         */
        private fun getRetrofitInstance(): Retrofit {
            return retrofitInstance ?: synchronized(this) {

                "https://www.thebluealliance.com/api/v3/".let { tempApiUrl ->
                    apiUrl = tempApiUrl

                    val tempInstance = Retrofit.Builder()
                        .baseUrl(tempApiUrl)
                        .addConverterFactory(
                            GsonConverterFactory.create(
                                GsonInstance.getInstance()
                            )
                        )
                        .client(getOkHttpInstance())
                        .build()

                    retrofitInstance = tempInstance
                    tempInstance
                }
            }
        }

        /**
         * Creates a new [OkHttpClient] and stores it into [okHttpInstance]
         * @param context [Context] current app context
         */
        private fun getOkHttpInstance(): OkHttpClient {
            return okHttpInstance ?: synchronized(this) {
                val tempOkHttpClient =
                    OkHttpClient.Builder().apply {
                        addInterceptor(AuthKeyInterceptor())
                        addInterceptor(loggingInterceptor)

                        connectTimeout(60, TimeUnit.SECONDS)
                        readTimeout(3, TimeUnit.MINUTES)
                        writeTimeout(2, TimeUnit.MINUTES)
                    }.build()

                okHttpInstance = tempOkHttpClient
                tempOkHttpClient
            }
        }

        /**
         * Creates an new [Api] instance and stores it into [instance]
         * @param context [Context] current app context
         */
        fun getInstance(): ScraperInstance {
            return instance ?: synchronized(this) {
                val tempInstance = getRetrofitInstance().create(ScraperInstance::class.java)
                instance = tempInstance
                tempInstance
            }
        }

        /**
         * Destroys all instances
         */
        fun destroyInstance() {
            instance = null
            retrofitInstance = null
            okHttpInstance = null
            apiUrl = null
        }

        /**
         * Sets the [loggingInterceptor] [HttpLoggingInterceptor.Level] per [Api] call
         * @param level [HttpLoggingInterceptor.Level] level of logging to apply to the [Api]
         */
        fun setInterceptorLevel(level: HttpLoggingInterceptor.Level) {
            loggingInterceptor.level = level
        }

        /**
         * Intercepts traffic and adds an auth key on each call
         */
        private class AuthKeyInterceptor() : Interceptor {
            override fun intercept(chain: Interceptor.Chain): Response = chain.run {
                proceed(
                    request()
                        .newBuilder()
                        .addHeader(
                            "X-TBA-Auth-Key",
                            Credentials.TBA_KEY
                        )
                        .build()
                )
            }
        }
    }

}