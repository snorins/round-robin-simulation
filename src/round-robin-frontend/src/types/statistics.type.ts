export type Statistics = {
    tournaments: StatisticsTournament[],
    teams: StatisticsTeam[],
}

type StatisticsTournament = {
    id: number;
    name: string;
    score: number;
}

type StatisticsTeam = {
    id: number;
    name: string;
    score: number;
}
