# KIT-FFA
Modo de juego todos contra todos donde cada usuario podra elegir su kit para la batalla y pelear por el puesto número uno en la tabla.

# Set-Up

Add Arena
- /setkffa [MapName] [SpawnCoords]

Game
- /kffa join (Entrar al mini-juego)
- /kffa quit (Salir del mini-juego)
- /kffa ktop kills (Spawn Leaderboard)
- /kffa ktop deaths (Spawn Leaderboard)

## Information

Como eliminar la arena? 
- En tu carpeta de Plugin_data busca la carpeta del plugin y entra al config.yml y deja los parametros "arena" en blanco -> []
Ejemplo:
  arena:
    name: []
    coords: []
    spawn: "3"
  
Como eliminar los Leaderboard?
- Dentro del juego usa un Pico de Piedra y has click en la posición donde esta el Leaderboard.
