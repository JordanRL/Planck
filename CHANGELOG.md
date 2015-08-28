# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased] - [unreleased]
### Added
- CHANGELOG.md (this file)
- "Roadmap" section in README.md
- Package meta-information in composer.json
- Docblocks for all methods in the project

### Changed
- Bad spelling/grammar in README.md (axis -> axes)
- Changed AbstractGrid constructor to set self address when a non-empty address is provided, instead of creating it as a sub-address

### Removed
- __construct() from GridInterface. (AbstractGrid should satisfy this on its own, and this allows more flexibility in extension.)

## [0.1.1] - 2015-08-24
### Added
- **New Dependency (All):** PHP >=5.5.0

### Changed
- Fixes for README.md

## 0.1.0 - 2015-08-24
### Added
- Initial preview release

[unreleased]: https://github.com/JordanRL/Planck/compare/v0.1.1...HEAD
[0.1.1]: https://github.com/JordanRL/Planck/compare/v0.1.0...v0.1.1
