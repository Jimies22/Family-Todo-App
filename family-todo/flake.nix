{
  description = "A PHP project for Family-Todo-App";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-23.11";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};

        php = pkgs.php82.withExtensions ({ enabled, all }: enabled ++ [
          all.curl
          all.mbstring
          all.openssl
          all.pdo
          all.pdo_mysql
          all.tokenizer
          all.xml
          all.zip
        ]);
      in {
        devShells.default = pkgs.mkShell {
          buildInputs = [
            php
            pkgs.php82Packages.composer
            pkgs.nodejs-18_x
            pkgs.nodejs-18_x
          ];

          shellHook = ''
            export PATH=$PWD/vendor/bin:$PATH
            echo "🚀 PHP $(php -r 'echo PHP_VERSION;') | Node $(node -v)"
          '';
        };
      }
    );
}
