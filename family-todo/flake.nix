
{
  description = "A PHP project for Family-Todo-App";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs {
          inherit system;
        };
        php = pkgs.php82;
        composer = pkgs.composer;
      in
      {
        devShell = pkgs.mkShell {
          buildInputs = [
            php
            composer
            pkgs.nodejs-18_x
            pkgs.npm
          ];
          
          shellHook = ''
            export PATH=$PWD/vendor/bin:$PATH
          '';
        };
      }
    );
}
