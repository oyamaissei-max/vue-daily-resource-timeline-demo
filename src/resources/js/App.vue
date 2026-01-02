<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue'
import { useDisplay, useDate } from 'vuetify'

const { name } = useDisplay()
const adapter = useDate()
const calendar = ref(null)

// ---------------------------
// 1. 日付操作と表示ロジック
// ---------------------------

const today = new Date()
const toYmd = (d) => d.toISOString().substr(0, 10)
const focus = ref(toYmd(today))

const prevDay = () => calendar.value?.prev()
const nextDay = () => calendar.value?.next()
const setToday = () => { focus.value = toYmd(new Date()) }

// ヘッダータイトル用のフォーマッタ
const formatter = new Intl.DateTimeFormat('ja-JP', { year: 'numeric', month: 'numeric' })
const headerTitle = computed(() => {
  if (!focus.value) return ''
  const date = new Date(focus.value)
  return formatter.format(date)
})

// 日付の数字だけを表示するフォーマッタ
const dayFormatter = (obj) => {
  return new Date(obj.date).getDate()
}

// ---------------------------
// 2. レスポンシブ＆ページネーション
// ---------------------------
const page = ref(1)
const itemsPerPage = computed(() => {
  switch (name.value) {
    case 'xs': return 1
    case 'sm': return 2
    case 'md': return 3
    case 'lg': return 5
    case 'xl': return 7
    default: return 4
  }
})

const allResources = ref(Array.from({ length: 20 }, (_, i) => `Resource ${i + 1}`))
const totalPages = computed(() => Math.ceil(allResources.value.length / itemsPerPage.value))
const visibleResources = computed(() => {
  const start = (page.value - 1) * itemsPerPage.value
  return allResources.value.slice(start, start + itemsPerPage.value)
})

watch(totalPages, (newTotal) => {
  if (page.value > newTotal) page.value = newTotal
})

const nextPage = () => { if (page.value < totalPages.value) page.value++ }
const prevPage = () => { if (page.value > 1) page.value-- }

// ---------------------------
// 3. 予約イベントデータ
// ---------------------------
const y = today.getFullYear()
const m = today.getMonth()
const d = today.getDate()

const events = reactive([
  {
    title: 'Event 1 (AM)',
    start: new Date(y, m, d, 9, 0),
    end: new Date(y, m, d, 11, 0),
    color: 'cyan',
    category: 'Resource 1',
    timed: true,
  },
  {
    title: 'Event 2 (PM)',
    start: new Date(y, m, d, 13, 0),
    end: new Date(y, m, d, 15, 30),
    color: 'orange',
    category: 'Resource 2',
    timed: true,
  },
  {
    title: 'Event 6 (Page 2)', 
    start: new Date(y, m, d, 10, 0),
    end: new Date(y, m, d, 12, 0),
    color: 'pink',
    category: 'Resource 6',
    timed: true,
  },
  {
    title: 'Event 20 (Last)',
    start: new Date(y, m, d, 14, 0),
    end: new Date(y, m, d, 16, 0),
    color: 'purple',
    category: 'Resource 20',
    timed: true,
  },
])

function getEventColor(event) {
  return event.color
}

onMounted(() => {
  if (calendar.value) {
    calendar.value.checkChange()
  }
})
</script>

<template>
  <v-app>
    <v-main>
      <v-container fluid class="fill-height">
        <v-card class="d-flex flex-column" height="100%" width="100%">
          
          <v-toolbar flat density="compact">
            <v-toolbar-title class="text-subtitle-1 font-weight-bold d-none d-sm-flex">
              予約状況
            </v-toolbar-title>

            <v-spacer class="d-none d-sm-flex"></v-spacer>

            <div class="d-flex align-center mr-2 bg-grey-lighten-4 rounded px-1">
              <v-btn icon size="x-small" density="comfortable" @click="prevPage" :disabled="page === 1">
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
              <span class="text-caption font-weight-bold mx-1" style="text-align: center;">
                {{ page }}/{{ totalPages }}
              </span>
              <v-btn icon size="x-small" density="comfortable" @click="nextPage" :disabled="page === totalPages">
                <v-icon>mdi-chevron-right</v-icon>
              </v-btn>
            </div>

            <v-divider vertical inset class="mx-1"></v-divider>

            <div class="d-flex align-center ml-1">
              <v-btn icon size="small" variant="text" @click="prevDay">
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
              
              <div class="text-body-2 font-weight-bold mx-2" style="white-space: nowrap; min-width: 80px; text-align: center;">
                {{ headerTitle }}
              </div>

              <v-btn icon size="small" variant="text" @click="nextDay">
                <v-icon>mdi-chevron-right</v-icon>
              </v-btn>

              <v-btn variant="outlined" size="small" class="ml-2" @click="setToday">
                今日
              </v-btn>
            </div>
          </v-toolbar>

          <v-card-text class="flex-grow-1 overflow-hidden">
            <v-calendar
              ref="calendar"
              v-model="focus"
              color="primary"
              type="category"
              :categories="visibleResources"
              :events="events"
              :event-color="getEventColor"
              event-name="title"
              category-show-all
              category-hide-dynamic
              locale="ja"
              :day-format="dayFormatter"
            ></v-calendar>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<style scoped>
.v-calendar {
  height: 100%;
}

/* 修正: 曜日を中央寄せするスタイル
  ご提示いただいたHTMLクラス "v-calendar-daily_head-weekday" (アンダースコア1つ) 
  および標準仕様 (アンダースコア2つ) の両方に対応させます。
  Tailwindの影響を考慮して !important を付与します。
*/
:deep(.v-calendar-daily_head-weekday) {
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  width: 100% !important;
  text-align: center !important;
}
</style>