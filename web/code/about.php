<?php
require('inc/checklogin.php');
$Title="About Bashu OnlineJudge";
?>
<!DOCTYPE html>
<html>
  <?php require('head.php'); ?>
  <body>
    <?php require('page_header.php') ?>  
          
    <div class="container-fluid about-page">
      <div class="row-fluid">
        <div class="offset2 span8" style="font-size:16px">
          <div class="page-header">
            <h2>About</h2>
          </div>
          <p>Bashu OnlineJudge is a system designed for OI training. You can find problems in the problemset
             and submit solutions. OnlineJudge system allows you to test your solution for every problem.</p>
          <p>Bashu OnlineJudge is an open source project.</p>
          <p>&nbsp;</p>
          <p>Developers:<br>&nbsp;&nbsp;Zhang YuXiang<br>&nbsp;&nbsp;Yang Zhixuan</p>
        </div>
      </div>
      <div class="row-fluid">
        <div class="offset2 span8" style="font-size:16px">
          <div class="page-header">
            <h2>FAQ</h2>
          </div>
          <div>
            <h3>Q: What compilers does the judge use and what are the compiler options?</h3>
            A: We are using GNU GCC/G++ for C/C++ compilation, and Free Pascal for pascal compilation. The compiler options are:
            <table class="table table-striped table-bordered table-condensed table-last-left-aligned" id="tab_options">
              <tbody>
                <tr>
                  <td>C++</td>
                  <td>g++ -static -fno-asm -s -w -O -DONLINE_JUDGE</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>gcc -static -fno-asm -s -w -O -DONLINE_JUDGE</td>
                </tr>
                <tr>
                  <td>Pascal</td>
                  <td>fpc -Xs -Sgic -dONLINE_JUDGE</td>
                </tr>
                <tr>
                  <td>C++ 11</td>
                  <td>g++ -static --std=gnu++0x -O -DONLINE_JUDGE</td>
                </tr>
              </tbody>
            </table>
          </div>
          <hr>
          <div>
            <h3>Q: From/to where should the program read/write the input/output?</h3>
            A: Your program shall read input from stdin('Standard Input') and write output to stdout('Standard Output'). To be specific, you can use 'scanf' in C and 'cin' in C++ to read from stdin, and use 'printf' in C and 'cout' in C++ to write to stdout.<br>User programs are not allowed to open and read from/write to files, "Runtime Error" would occure if your programs try to do so.
          </div>
          <hr>
          <div>
            <h3>Q: What do the judge's replies mean?</h3>
            A: Here is a list of the judge's replies and their meaning:<br>
            <table class="table table-condensed table-last-left-aligned" id="about_msg">
              <tbody>
                <tr>
                  <td><span class="label label-success">Accepted</span></td>
                  <td>OK! Your program is correct!</td>
                </tr>
                <tr>
                  <td><span class="label">Compile Error</span></td>
                  <td>The compiler could not compile your program. Of course, warnings during compilation wouldn't be regarded as errors.</td>
                </tr>
                <tr>
                  <td><span class="label label-warning">Memory Exceeded</span></td>
                  <td>Your program tried to use more memory than the limit indicated by the problem settings.</td>
                </tr>
                <tr>
                  <td><span class="label label-info">Runtime Error</span></td>
                  <td>Your program failed during the execution (illegal file access, stack overflow, pointer reference out of range, floating point exception, divided by zero, etc).</td>
                </tr>
                <tr>
                  <td><span class="label label-warning">Time Out</span></td>
                  <td>Your program failed to ouput an answer within the time limit of the problem.</td>
                </tr>
                <tr>
                  <td><span class="label label-important">Wrong Answer</span></td>
                  <td>Your solution has not produced the desired output.</td>
                </tr>
                <tr>
                  <td><span class="label label-inverse">Validator Error</span></td>
                  <td>The checker program has exhibited abnormal behavior while validating the output produced by the solution.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <hr>
          <h5>Any questions/suggestions please post to <a href="board.php">web board</a>.</h5>
        </div>
      </div>
      <hr>
      <footer>
        <p>&copy; 2012-2014 Bashu Middle School</p>
      </footer>
    </div>
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/common.js"></script>
    <script type="text/javascript"> 
      $(document).ready(function(){
        $('#ret_url').val("about.php");
        $('#nav_about').parent().addClass('active');
      });
    </script>
  </body>
</html>
