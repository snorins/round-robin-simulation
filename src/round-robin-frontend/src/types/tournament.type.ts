export type Tournament = {
    id: number;
    name: string;
    teamCount: number;
    createdAt: number;
}

export type TournamentView = {
    name: string;
    teams: TournamentViewTeam[]
    rounds: TournamentViewRound[]
}

type TournamentViewTeam = {
    id: number;
    name: string;
    wins: number;
    score: number;
}

type TournamentViewRound = {
    current: number;
    games: TournamentViewGame[]
}

type TournamentViewGame = {
    id: number;
    teamOne: {
        id: number;
        name: string;
        score: number;
    },
    teamTwo: {
        id: number;
        name: string;
        score: number;
    }
}
