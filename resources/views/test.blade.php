<!DOCTYPE html>
<html>
<head>
 <title>speak text to speech with Resvonsive Voice</title>
 <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
 <script type="text/javascript">
  function play (){
   responsiveVoice.speak(
    "Antrian A, 002, Menuju, Loket, 1",
    "Indonesian Female",
    {
     pitch: 1, 
     rate: 0.85, 
     volume: 1
    }
   );
  }

  function stop (){
   responsiveVoice.cancel();
  }

  function pause (){
   responsiveVoice.pause();
  }

  function resume (){
   responsiveVoice.resume();
  }
 </script>
</head>
<body>
 <button onclick="play();">Play</button>
 <button onclick="stop();">Stop</button>
 <button onclick="pause();">Pause</button>
 <button onclick="resume();">Resume</button>
</body>
</html>
