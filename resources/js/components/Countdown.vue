<template>
    <div class="flex items-center justify-center">
        <div class="timer-unit text-center px-3 md:px-6">
            <div
                class="timer-number text-lg md:text-3xl font-bold"
                id="days-desktop"
            >
                {{ days }}
            </div>
            <div class="timer-label text-xs md:text-sm text-cyan-400">DAYS</div>
        </div>

        <!-- Pulsing separator -->
        <div class="timer-separator"></div>

        <div class="timer-unit text-center px-3 md:px-6">
            <div
                class="timer-number text-lg md:text-3xl font-bold"
                id="hours-desktop"
            >
                {{ hours }}
            </div>
            <div class="timer-label text-xs md:text-sm text-cyan-400">HRS</div>
        </div>

        <!-- Pulsing separator -->
        <div class="timer-separator"></div>

        <div class="timer-unit text-center px-3 md:px-6">
            <div
                class="timer-number text-lg md:text-3xl font-bold"
                id="minutes-desktop"
            >
                {{ minutes }}
            </div>
            <div class="timer-label text-xs md:text-sm text-cyan-400">MINS</div>
        </div>

        <!-- Festival message when active -->
        <div v-if="phase === 'pit'" class="festival-message text-center">
            <span class="festival-text">See You In The Pit!</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "CountdownTimer",
    data() {
        return {
            days: "00",
            hours: "00",
            minutes: "00",
            phase: "countdown25",
            interval: null,
            festivalStart: new Date("2025-06-12T00:00:00").getTime(),
            festivalEnd: new Date("2025-06-16T00:00:00").getTime(),
            nextDownloadStart: new Date("2026-06-10T00:00:00").getTime(),
        };
    },
    mounted() {
        this.updateTimer();
        this.interval = setInterval(this.updateTimer, 1000);
    },
    beforeUnmount() {
        if (this.interval) {
            clearInterval(this.interval);
        }
    },
    methods: {
        getPhaseAndTarget() {
            const now = Date.now();
            if (now < this.festivalStart) {
                return { phase: "countdown25", target: this.festivalStart };
            } else if (now >= this.festivalStart && now < this.festivalEnd) {
                return { phase: "pit", target: null };
            } else {
                return { phase: "countdown26", target: this.nextDownloadStart };
            }
        },
        updateTimer() {
            const phaseAndTarget = this.getPhaseAndTarget();
            this.phase = phaseAndTarget.phase;

            if (phaseAndTarget.phase === "pit") {
                this.days = "--";
                this.hours = "--";
                this.minutes = "--";
                return;
            }

            const targetDate = phaseAndTarget.target;
            const currentDate = new Date().getTime();
            const distance = targetDate - currentDate;

            if (distance > 0) {
                this.days = Math.floor(distance / (1000 * 60 * 60 * 24))
                    .toString()
                    .padStart(2, "0");
                this.hours = Math.floor(
                    (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                )
                    .toString()
                    .padStart(2, "0");
                this.minutes = Math.floor(
                    (distance % (1000 * 60 * 60)) / (1000 * 60)
                )
                    .toString()
                    .padStart(2, "0");
            } else {
                this.days = "00";
                this.hours = "00";
                this.minutes = "00";
            }
        },
    },
};
</script>

<style scoped>
.festival-text {
    display: inline-block;
    font-size: 1.5rem;
    font-weight: 900;
    letter-spacing: 0.05em;
    color: #fff;
    text-shadow: 0 0 16px #00ffe6, 0 0 32px #00ffe6, 0 2px 8px #000,
        0 0 2px #00ffe6;
    font-family: "Oswald", "Impact", "Arial Black", sans-serif;
    padding: 0.5em 0;
}

@media (min-width: 768px) {
    .festival-text {
        font-size: 2.2rem;
    }
}

.timer-unit {
    transition: opacity 0.3s ease;
}

.timer-number {
    font-family: "Oswald", "Impact", "Arial Black", sans-serif;
    color: #fff;
}

/* Pulsing separator bars */
.timer-separator {
    width: 1px;
    height: 30px;
    background: linear-gradient(to bottom, transparent, #00ffe6, transparent);
    margin: 0 8px;
    animation: pulse-height 2s ease-in-out infinite;
    border-radius: 1px;
    box-shadow: 0 0 10px rgba(0, 255, 230, 0.5);
}

@media (min-width: 768px) {
    .timer-separator {
        width: 2px;
        height: 40px;
        margin: 0 10px;
    }
}

/* Height pulsing animation for separators */
@keyframes pulse-height {
    0%,
    100% {
        opacity: 0.6;
        box-shadow: 0 0 5px rgba(0, 255, 230, 0.3);
    }
    50% {
        opacity: 1;
        box-shadow: 0 0 20px rgba(0, 255, 230, 0.8),
            0 0 40px rgba(0, 255, 230, 0.4);
    }
}

/* Timer Numbers */
.timer-number {
    font-weight: bold;
}

/* Number Labels */
.timer-label {
    color: #0891b2;
    margin-top: 5px;
}
</style>
